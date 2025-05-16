module.exports = {
    root: true,
    parser: '@typescript-eslint/parser',
    parserOptions: {
        ecmaVersion: 2020,
        sourceType: 'module',
        ecmaFeatures: {
            jsx: true,
        },
    },
    plugins: ['@typescript-eslint', 'react', 'react-hooks', 'import', 'jsx-a11y'],
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',
        'plugin:react/recommended',
        'plugin:jsx-a11y/recommended',
        'plugin:react-hooks/recommended',
        'plugin:import/errors',
        'plugin:import/warnings',
        'plugin:import/typescript',
        'prettier',
    ],
    rules: {
        'react/react-in-jsx-scope': 'off', // not needed for React 17+
        'react/prop-types': 'off', // since we're using TypeScript
        '@typescript-eslint/explicit-module-boundary-types': 'off',
    },
    settings: {
        react: {
            version: 'detect',
        },
    },
};
