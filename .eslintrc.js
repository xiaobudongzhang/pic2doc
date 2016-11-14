module.exports = {
    "extends": "airbnb",
    "plugins": [
        "react",
        "jsx-a11y",
        "import"
    ],
    "rules": {
      "strict": 0,
      "comma-dangle": 0,
      "no-alert": 0,
      "no-var": 0,
      "func-names": 0,
      "object-shorthand": 0,
      "prefer-arrow-callback": 0,
      "no-console": 0,
      "no-param-reassign": ["error", { "props": false }],
      "react/jsx-filename-extension": [1, { "extensions": [".js"] }],
      "max-len": ["error", 200],
      "no-underscore-dangle": 0
    }
};
