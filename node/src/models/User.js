const { Model, DataTypes } = require("sequelize");
const bcryptjs = require("bcryptjs");

class User extends Model {
  static init(sequelize) {
    super.init(
      {
        id: {
          type: DataTypes.INTEGER,
          autoIncrement: true,
          primaryKey: true,
          allowNull: false,
        },
        password: {
          type: DataTypes.STRING,
          allowNull: false,
          validate: {
            notEmpty: true,
            is: /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^\w\s]).*$/g,
          },
        },
        email: {
          type: DataTypes.STRING,
          allowNull: false,
          validate: {
            isEmail: {
              msg: "Email invalid",
            },
          },
        },
        createdAt: {
          type: DataTypes.DATE,
          allowNull: true,
        },
        updatedAt: {
          type: DataTypes.DATE,
          allowNull: true,
        },
      },
      {
        sequelize,
        tableName: "user",
        timestamps: true,
        underscored: false,
      }
    );

    this.addHook("afterCreate", async (user, options) => {
      const numSaltRounds = +process.env.NUM_SALT_ROUNDS || 8;

      const hash = await bcryptjs.hash(user.password, numSaltRounds);

      await user.update(
        { password: hash },
        { transaction: options.transaction }
      );
    });
  }

  static associate(models) {
    this.hasMany(models.Project, { as: "projects" });
  }
}

module.exports = User;
