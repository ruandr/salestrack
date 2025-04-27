import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000/api';

const token = localStorage.getItem('token');
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

const api = axios.create({
    headers: {
        'Content-Type': 'application/json',
    },
});

export default api;
