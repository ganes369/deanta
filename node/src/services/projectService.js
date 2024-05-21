const { Sequelize } = require("sequelize");
const { Op } = Sequelize;
const Project = require("../models/Project");

const ProjectService = {
  async createProject({ title, content, id }) {
    const newProject = await Project.create({ title, content, userId: id });
    return newProject;
  },

  async updateProject({ title, content, idProject, id }) {
    const find = await Project.findOne({
      where: {
        [Op.and]: [{ id: idProject }, { userId: id }],
      },
    });

    if (!find) return false;
    await Project.update(
      { title, content },
      {
        where: {
          [Op.and]: [{ id: idProject }, { userId: id }],
        },
      }
    );
    return true;
  },
};

module.exports = ProjectService;
