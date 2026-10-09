import globals from 'globals'
import tseslint from 'typescript-eslint'

export default tseslint.config(
  {
    // Downstream bundles are minified build output from their own repositories;
    // each is linted where it is authored rather than from a nested theme copy.
    ignores: ['node_modules/**', 'spectre-theme/dist/**', 'spectre-child-*/assets/js/*.js'],
  },
  tseslint.configs.recommended,
  {
    files: ['**/*.{ts,tsx,js,jsx}'],
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
    rules: {
      'no-console': 'warn',
      '@typescript-eslint/no-unused-vars': ['warn', { argsIgnorePattern: '^_' }],
      '@typescript-eslint/no-explicit-any': 'warn',
    },
  },
  {
    files: ['scripts/**/*.{ts,js}'],
    rules: {
      'no-console': 'off',
    },
  }
)
