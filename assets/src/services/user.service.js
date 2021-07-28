import axios from 'axios';
import authHeader from './auth-header';
import AuthService from "../services/auth.service";

const API_URL = 'http://127.0.0.1:8000/api/';

const currentUser = AuthService.getCurrentUser();
class UserService {

    getOtherUsers() {
        return axios.get(API_URL + 'notification/users', { headers: authHeader() });
    }

    getSentUsers() {
        return axios.get(API_URL + 'notification/invitations-sent', { headers: authHeader() });
    }

    getRecieverInvitation() {
        return axios.get(API_URL + 'notification/invitations-received', { headers: authHeader() });
  }

}

export default new UserService();