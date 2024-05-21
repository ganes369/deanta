const UserService = require("../services/userService");

const UserController = {
  async createUser(req, res) {
    try {
      const { email, password } = req.body;
      const newUser = await UserService.createUser({
        email,
        password,
      });

      return res.status(201).json(newUser);
    } catch (error) {
      console.error("Error creating user:", error);
      if (error.name === "SequelizeValidationError") {
        const validationErrors = error.errors.map((err) => err.message);
        return res.status(400).json({ errors: validationErrors });
      }
      return res.status(500).json({ error: error.message });
    }
  },

  async updateUser(req, res) {
    try {
      const { email, password, id, token } = req.body;
      await UserService.updateUser({
        email,
        password,
        id,
        token,
      });

      return res.status(204).json("log in again");
    } catch (error) {
      console.error("Error update user:", error);
      if (error.name === "SequelizeValidationError") {
        const validationErrors = error.errors.map((err) => err.message);
        return res.status(400).json({ errors: validationErrors });
      }
      return res.status(500).json({ error: error.message });
    }
  },

  async getAllUsers(req, res) {
    try {
      const getAllUser = await UserService.getAllUser();
      return res.status(200).json(getAllUser);
    } catch (error) {
      if (error.name === "SequelizeValidationError") {
        const validationErrors = error.errors.map((err) => err.message);
        return res.status(400).json({ errors: validationErrors });
      }
      return res.status(500).json({ error: error.message });
    }
  },
};

module.exports = UserController;
