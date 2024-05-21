const { Model, DataTypes } = require("sequelize");

class Article extends Model {
  static init(sequelize) {
    super.init(
      {
        id: {
          type: DataTypes.INTEGER,
          autoIncrement: true,
          primaryKey: true,
          allowNull: false,
        },
        title: {
          type: DataTypes.STRING,
          allowNull: false,
          validate: {
            notEmpty: {
              msg: "Title cannot be empty",
            },
            len: {
              args: [5, 100],
              msg: "Title must be between 5 and 100 characters",
            },
          },
        },
        description: {
          type: DataTypes.STRING,
          allowNull: false,
          validate: {
            notEmpty: {
              msg: "Description cannot be empty",
            },
            len: {
              args: [10, 255],
              msg: "Description must be between 10 and 255 characters",
            },
          },
        },
        text: {
          type: DataTypes.TEXT,
          allowNull: false,
          validate: {
            notEmpty: {
              msg: "Text cannot be empty",
            },
            len: {
              args: [20, 5000],
              msg: "Text must be between 20 and 5000 characters",
            },
          },
        },
        projectId: {
          type: DataTypes.INTEGER,
          allowNull: false,
          references: {
            model: "project", // Referenced table name
            key: "id",
          },
          onUpdate: "CASCADE",
          onDelete: "CASCADE",
          validate: {
            isInt: {
              msg: "Project ID must be an integer",
            },
          },
        },
        createdAt: {
          allowNull: false,
          type: DataTypes.DATE,
          defaultValue: DataTypes.NOW,
        },
        updatedAt: {
          allowNull: false,
          type: DataTypes.DATE,
          defaultValue: DataTypes.NOW,
        },
      },
      {
        sequelize,
        tableName: "article",
        timestamps: true,
        underscored: false,
      }
    );
  }

  static associate(models) {
    this.belongsTo(models.Project, { foreignKey: "projectId", as: "project" });
  }
}

module.exports = Article;
