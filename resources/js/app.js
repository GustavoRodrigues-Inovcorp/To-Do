import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';

const app = createApp({});
app.component('example-component', ExampleComponent);
app.mount('#app');