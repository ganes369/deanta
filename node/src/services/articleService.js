const Article = require("../models/Article");
const Project = require("../models/Project");
const User = require("../models/User");

const ArticleService = {
  async createArticle({ title, description, text, projectId }) {
    const newArticle = await Article.create({
      title,
      description,
      text,
      projectId,
    });
    return newArticle;
  },

  async updateArticle({ title, description, text, projectId, id }) {
    const find = await Article.findOne({ where: { id: id } });
    if (!find) return;
    const update = await Article.update(
      {
        title,
        description,
        text,
        projectId,
      },
      {
        where: { id: id },
      }
    );
    return update;
  },

  async listOneArticle({ id }) {
    const find = await Article.findOne({
      where: { id: id },
      include: [
        {
          model: Project,
          as: "project", // ou o alias que você definiu na associação
        },
      ],
    });
    if (!find) return;

    return find;
  },

  async listAllArticle() {
    const find = await Article.findAll({
      include: [
        {
          model: Project,
          as: "project", // ou o alias que você definiu na associação
        },
      ],
    });
    if (!find) return;

    return find;
  },

  async deleteArticle({ userId, idArticle }) {
    console.log(userId, idArticle);
    const find = await Article.findOne({
      where: { id: idArticle },
      include: [
        {
          model: Project,
          as: "project", // ou o alias que você definiu na associação
          include: [
            {
              model: User,
              as: "user",
            },
          ],
        },
      ],
    });
    if (!find || find.project.UserId !== userId) return;

    await Article.destroy({ where: { id: idArticle } });
    return true;
  },
};

module.exports = ArticleService;
