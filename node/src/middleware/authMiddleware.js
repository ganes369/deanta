const Blacklist = require("../models/BlackList");
const AuthService = require("../services/authService");

const CreateAuthMiddleware = async (_, res, next) => {
  // Intercepta o método `status` original para capturar o status da resposta
  const originalStatus = res.status;
  let responseStatus;

  res.status = (statusCode) => {
    responseStatus = statusCode;
    return originalStatus.call(res, statusCode);
  };

  // Intercepta o método `send` original
  const originalSend = res.send;

  res.send = (body) => {
    // Verifica se o status é 201 e autentica requisição
    if ((responseStatus || res.statusCode) === 201) {
      const { email, id } = JSON.parse(body);
      const token = AuthService.generateToken({ email, id });
      res.setHeader("token", token);
    }

    // Chama o método `send` original com o corpo modificado
    return originalSend.call(res, body);
  };

  // Passa para o próximo middleware/rota
  next();
};

const Authentication = async (req, res, next) => {
  try {
    const token = req.headers["authorization"];
    const isValidToken = AuthService.verifyToken(token.split(" ")[1]);

    if (!isValidToken) return res.status(403).json("expired token");

    const findToken = await Blacklist.findOne({
      where: { token: token.split(" ")[1], status: 1 },
    });

    if (!findToken) return res.status(403).json("invalid token");
    req.body.id = isValidToken.id;
    req.body.token = token.split(" ")[1];
    next();
  } catch (error) {
    return res.status(500).json({ error });
  }
};

module.exports = { CreateAuthMiddleware, Authentication };
