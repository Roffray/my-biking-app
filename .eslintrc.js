// https://eslint.org/docs/user-guide/configuring

module.exports = {
    root: true,
    // parserOptions: {
    //   parser: 'babel-eslint'
    // },
    env: {
        es6: true,
        browser: true,
    },
    extends: [
        'eslint:recommended',
        'airbnb',
        // https://github.com/vuejs/eslint-plugin-vue#priority-a-essential-error-prevention
        /*
         * consider switching to `plugin:vue/strongly-recommended`
         * or `plugin:vue/recommended` for stricter rules.
         */
        'plugin:vue/recommended',
    ],
    // required to lint *.vue files
    plugins: [
        'vue',
    ],
    // add your custom rules here
    rules: {
        // allow async-await
        'generator-star-spacing': 'off',
        // allow debugger during development
        'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'import/extensions': 0,
        'import/no-unresolved': 0,
        indent: ['error', 4],
        'vue/html-indent': ['error', 4],
    },
};
