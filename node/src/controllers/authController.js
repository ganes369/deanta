const AuthService = require("../services/authService");

const LoginController = {
  async loginUser(req, res) {
    try {
      const { email, password } = req.body;

      const user = await AuthService.login({
        email,
        password,
      });

      if (!user) return res.status(400).json("Invalid email or password");
      return res.status(201).json(user);
    } catch (error) {
      console.error("Error creating user:", error);
      return res.status(500).json({ error: error.message });
    }
  },
};

module.exports = LoginController;
