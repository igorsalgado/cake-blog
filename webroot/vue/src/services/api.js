import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8765/api',
    headers: {
        'Content-Type': 'application/json',
    },
});

export default api;
