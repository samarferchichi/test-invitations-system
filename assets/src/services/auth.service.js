import axios from "axios";

const API_URL = "http://127.0.0.1:8000/";

class AuthService {
  login(username, password) {
    return axios
      .post(API_URL + "api/" + "login_check", {
        username,
        password
      })
      .then(response => {
        if (response.data.token) {
          localStorage.setItem("user", JSON.stringify(response.data));
          localStorage.setItem("username", username);

        }

        return response.data;
      });
  }

  logout() {
    localStorage.removeItem("user");
  }

  register(username, email, password) {
    return axios.post(API_URL + "register/", {
      username,
      email,
      password
    });
  }

  getCurrentUser() {
    return JSON.parse(localStorage.getItem('user'));
  }

  getCurrentUserName() {
    return localStorage.getItem('username');
  }
}

export default new AuthService();