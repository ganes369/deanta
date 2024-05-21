const ProjectService = require("../services/projectService");

const ProjectController = {
  async createProject(req, res) {
    try {
      const { title, content, id } = req.body;
      const newProject = await ProjectService.createProject({
        title,
        content,
        id,
      });

      return res.status(201).json(newProject);
    } catch (error) {
      console.error("Error creating project:", error);
      if (error.name === "SequelizeValidationError") {
        const validationErrors = error.errors.map((err) => err.message);
        return res.status(400).json({ errors: validationErrors });
      }
      return res.status(500).json({ error: error.message });
    }
  },

  async updateProject(req, res) {
    try {
      const { title, content, id } = req.body;
      const { id: idProject } = req.query;

      const update = await ProjectService.updateProject({
        title,
        content,
        idProject,
        id,
      });
      if (!update)
        return res.status(404).json({ message: "resource not found" });
      return res.status(204).json();
    } catch (error) {
      console.error("Error update project:", error);
      if (error.name === "SequelizeValidationError") {
        const validationErrors = error.errors.map((err) => err.message);
        return res.status(400).json({ errors: validationErrors });
      }
      return res.status(500).json({ error: error.message });
    }
  },
};

module.exports = ProjectController;
