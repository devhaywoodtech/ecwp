import { createRoot } from 'react-dom/client';
import { Provider } from 'react-redux';
import store from '../store';
import Admin from './Admin.jsx';

const div = document.getElementById('ecwp-calendar-settings');
if (div != null) {
    const root = createRoot(div);
    const logo = div.getAttribute('data-logo'); 
    root.render(<Provider store={store}><Admin logo={logo} /></Provider>);
    setTimeout(console.log.bind(console, '%cEVENTS CALENDAR - WP MONTHLY EVENTS', 'color: #B721FF;font-weight:bold;font-size:20px'), 0);
}