const bcryptjs = require("bcryptjs");
const User = require("../models/User");
const AuthService = require("./authService");
const Project = require("../models/Project");

const UserService = {
  async createUser({ email, password }) {
    const newUser = (await User.create({ email, password })).dataValues;
    const { password: pass, ...newUserWithouPass } = newUser;
    return newUserWithouPass;
  },

  async updateUser({ email, password, id, token }) {
    const numSaltRounds = +process.env.NUM_SALT_ROUNDS || 8;
    const hash = await bcryptjs.hash(password, numSaltRounds);

    await User.update(
      { email, password },
      {
        where: { id: id },
      }
    );
    await User.update(
      { email, password: hash },
      {
        where: { id: id },
      }
    );

    await AuthService.invalidToken({ token });
    return undefined;
  },

  async getAllUser() {
    return await User.findAll({
      attributes: ["id", "email"],
      include: {
        model: Project,
        as: "projects",
      },
    });
  },
};

module.exports = UserService;
