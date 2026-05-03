import { mergeConfig } from 'vite';

const config = {
  stories: ['../src/components/stories/**/*.stories.@(js|jsx)'],
  addons: [
    '@storybook/addon-onboarding',
    '@storybook/addon-essentials',
    '@storybook/addon-interactions',
  ],
  framework: {
    name: '@storybook/react-vite',
    options: {},
  },
  async viteFinal(config) {
    return mergeConfig(config, {
      optimizeDeps: {
        include: ['@headlessui/react', 'lucide-react'],
      },
    });
  },
};

export default config;