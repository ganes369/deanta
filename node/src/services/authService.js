const jwt = require("jsonwebtoken");
const bcryptjs = require("bcryptjs");
const Blacklist = require("../models/BlackList");
const User = require("../models/User");

const AuthService = {
  generateToken(params = {}) {
    const token = jwt.sign(params, process.env.SECRET, {
      expiresIn: +process.env.EXPIRE_IN,
    });
    Blacklist.create({ status: 1, token, userId: params.id });
    return token;
  },
  verifyToken(token) {
    return jwt.verify(token, process.env.SECRET, function (err, decoded) {
      if (err) {
        return false;
      }
      return decoded;
    });
  },
  async invalidToken({ token }) {
    await Blacklist.update(
      { status: 0 },
      {
        where: { token: token },
      }
    );
    return;
  },

  async login({ email, password }) {
    const user = await User.findOne({
      where: {
        email: email,
      },
    });

    if (!user) return;

    const blackListFind = await Blacklist.findOne({
      where: { userId: user.id },
      order: [["id", "DESC"]],
    });

    await Blacklist.update({ status: 0 }, { where: { id: blackListFind.id } });
    const compare = await bcryptjs.compare(password, user?.password);

    if (!compare) return;
    return { email: user.email, id: user.id };
  },
};

module.exports = AuthService;
