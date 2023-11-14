import { registerReactControllerComponents } from '@symfony/ux-react';
import './bootstrap.js';

import "./i18n";

registerReactControllerComponents(require.context('./react/views', true, /\.(j|t)sx?$/));
