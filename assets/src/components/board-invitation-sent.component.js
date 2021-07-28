import React, { Component } from "react";
import authHeader from '../services/auth-header';
import axios from "axios";
import UserService from "../services/user.service";

const API_URL = 'http://127.0.0.1:8000/api/';

export default class BoardInvitationsSent extends Component {
  constructor(props) {
    super(props);

    this.state = {
      content: ""
    };
  }

  componentDidMount() {
    UserService.getSentUsers().then(
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

  handleCancelClick(param) {
    axios.post(API_URL + 'notification/delete-invitation/' + param, {}, { headers: authHeader()})
      .then( ( response ) => {
        window. location. reload(false);
      } )
      .catch()
  }

  render() {
    return (
      <div className="container">
        <header className="jumbotron">
        <table className="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">UserName</th>
              <th scope="col">Email</th>
              <th scope="col">Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          {Object.keys(this.state.content).map((keyName, i) => (
            <tr key={i}>
              <th scope="row"> {this.state.content[keyName].id} </th>
              <td> {this.state.content[keyName].receiver.username} </td>
              <td> {this.state.content[keyName].receiver.email} </td>
              <td> {this.state.content[keyName].status == 0 ? "PENDING" : this.state.content[keyName].status == 1 ? "ACCEPTED" : "REFUSED"} </td>
              {this.state.content[keyName].status == 0 ? <td> <button type="button" className="btn btn-danger" onClick={this.handleCancelClick.bind(this, this.state.content[keyName].id)}>Cancel</button>  </td>  : <td></td>}
            </tr>
          ))}
          </tbody>
        </table>
      
        </header>
      </div>
    );
  }
}