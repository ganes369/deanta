const ArticleService = require("../services/articleService");

const ArticleController = {
  async createArticle(req, res) {
    try {
      const { title, description, text, projectId } = req.body;
      console.log(title, description, text, projectId);
      const article = await ArticleService.createArticle({
        title,
        description,
        text,
        projectId,
      });

      return res.status(201).json(article);
    } catch (error) {
      console.error("Error creating article:", error);
      return res.status(500).json({ error: error.message });
    }
  },

  async updateArticle(req, res) {
    try {
      const { title, description, text, projectId } = req.body;
      const { id } = req.query;
      const article = await ArticleService.updateArticle({
        title,
        description,
        text,
        projectId,
        id,
      });

      if (!article)
        return res.status(404).json({ message: "resource not found" });
      return res.status(204).json();
    } catch (error) {
      console.error("Error update article:", error);
      return res.status(500).json({ error: error.message });
    }
  },

  async listOneArticle(req, res) {
    try {
      const { id } = req.params;
      const article = await ArticleService.listOneArticle({
        id,
      });

      if (!article)
        return res.status(404).json({ message: "resource not found" });
      return res.status(200).json(article);
    } catch (error) {
      console.error("Error listOne article:", error);
      return res.status(500).json({ error: error.message });
    }
  },

  async listAllArticle(_, res) {
    try {
      const article = await ArticleService.listAllArticle();

      if (!article)
        return res.status(404).json({ message: "resource not found" });
      return res.status(200).json(article);
    } catch (error) {
      console.error("Error listAll article:", error);
      return res.status(500).json({ error: error.message });
    }
  },

  async deleteArticle(req, res) {
    try {
      const { id } = req.body;
      const { articleId } = req.query;
      console.log(id, "111111111111111");
      const article = await ArticleService.deleteArticle({
        idArticle: articleId,
        userId: id,
      });

      if (!article)
        return res.status(404).json({ message: "resource not found" });
      return res.status(200).json(article);
    } catch (error) {
      console.error("Error delete article:", error);
      return res.status(500).json({ error: error });
    }
  },
};

module.exports = ArticleController;
