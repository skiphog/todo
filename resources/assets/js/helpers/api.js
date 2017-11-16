import axios from 'axios';
import Auth from './auth';

export function post (url, data) {
  return axios({
    method: 'post',
    url: url,
    data: data,
    headers: {
      'Authorization': `Bearer ${Auth.state.api_token}`
    }
  });
}

export function get (url) {
  return axios({
    method: 'get',
    url: url,
    headers: {
      'Authorization': `Bearer ${Auth.state.api_token}`
    }
  });
}

export function del (url) {
  return axios({
    method: 'DELETE',
    url: url,
    headers: {
      'Authorization': `Bearer ${Auth.state.api_token}`
    }
  });
}

export function interceptors (cb) {
  axios.interceptors.response.use((res) => {
    return res;
  }, (err) => {
    cb(err);
    return Promise.reject(err);
  });
}