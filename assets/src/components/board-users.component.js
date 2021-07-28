import React, { Component } from "react";

import UserService from "../services/user.service";
import axios from "axios";
import authHeader from '../services/auth-header';
import AuthService from "../services/auth.service";

const API_URL = 'http://127.0.0.1:8000/api/';
const currentUser = AuthService.getCurrentUser();
const getCurrentUserName = AuthService.getCurrentUserName()
export default class BoardUser extends Component {
  constructor(props) {
    super(props);
    this.state = {
      content: "",
      success_sent: "",
    };
  }

  componentDidMount() {
    UserService.getOtherUsers().then(
      response => {
        this.setState({
          content: response.data
        });
      },
      error => {
        this.setState({
          content:
            (error.response &&
              error.response.data &&
              error.response.data.message) ||
            error.message ||
            error.toString()
        });
      }
    );
  }

  handleClick(param) {
    axios.post(API_URL + 'notification/send-invitation', {receiver : param}, { headers: authHeader() })
      .then(res => {
        window. location. reload(false);
        this.setState({
          success_sent: 1
        });        
      })
  }

  render() {
    let check = false
    return (
      <div className="container">
        <header className="jumbotron">
        <table className="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">UserName</th>
              <th scope="col">Email</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
          {Object.keys(this.state.content).map((keyName, i) => (
            
            <tr key={i}>
              <th scope="row"> {this.state.content[keyName].id} </th>
              <td> {this.state.content[keyName].username} </td>
              <td> {this.state.content[keyName].email} </td>
            
              <td>
              {check = false}
              {Object.keys(this.state.content[keyName].receiver_notifications).map((key, i) => (
                
                this.state.content[keyName].receiver_notifications[key].sender.username == getCurrentUserName ?
                 check = true 
                 :
                 false 
              ))}
              {check !== true ? 
                <button type="button" className="btn btn-info" onClick={this.handleClick.bind(this, this.state.content[keyName].id)}>Envoyer</button>  
                :
                "Done"
              }
              </td>
            </tr>
          ))}
          </tbody>
        </table>
        </header>
      </div>
    );
  }
}