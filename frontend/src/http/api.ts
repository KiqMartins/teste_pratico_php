import axios, { type AxiosInstance, type InternalAxiosRequestConfig } from 'axios';

const api: AxiosInstance = axios.create({
  baseURL: 'http://localhost:8080',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

api.interceptors.request.use((config: InternalAxiosRequestConfig) => {
  const userId = localStorage.getItem('user_id');
  if (userId) {
    config.headers.set('X-User-Id', userId);
  }
  return config;
});

export default api;