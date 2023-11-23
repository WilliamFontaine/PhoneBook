import i18n from 'i18next';
import {initReactI18next} from 'react-i18next';

i18n
  .use(initReactI18next)
  .init({
    fallbackLng: 'en',
    debug: true,

    interpolation: {
      escapeValue: false,
    },
  });

const translationPath = './locales';

i18n.addResourceBundle('en', 'translation', require(`${translationPath}/en.json`));
i18n.addResourceBundle('fr', 'translation', require(`${translationPath}/fr.json`));

export default i18n;
