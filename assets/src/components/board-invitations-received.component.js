import React, { Component } from "react";
import authHeader from '../services/auth-header';
import UserService from "../services/user.service";
import axios from "axios";
const API_URL = 'http://127.0.0.1:8000/api/';
import TableFilter from 'react-table-filter';

export default class BoardInvitationsReceived extends Component {
  constructor(props) {
    super(props);

    this.state = {
      content: "",
      success_accept: "",
      query: ""
    };
  }

  componentDidMount() {
    UserService.getRecieverInvitation().then(
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

  handleAcceptClick(param) {
    axios.post(API_URL + 'notification/accept-invitation/' + param, {}, { headers: authHeader() })
      .then(res => {
        window. location. reload(false);     
      })
  }

  handleRefuseClick(param) {
    axios.post(API_URL + 'notification/cancel-invitation/' + param, {}, { headers: authHeader() })
      .then(res => {
        window. location. reload(false);       
      })
  }

  handleQueryChange(event) {
    this.setState({
      query: event.target.value
    })
  }

  filterInvitation() {
    if (Array.isArray(this.state.content)) {
      if (this.state.query.length > 0) {
        return this.state.content.filter(elem => {
          return elem.sender.username.indexOf(this.state.query) != -1 ||
            elem.sender.email.indexOf(this.state.query) != -1
        })
      } else {
        return this.state.content
      }
    } else {
      return []
    }
  }

  render() {
    var names = this.state.content;
    return (
      <div className="container">
        <div>
            <input className="form-control mb-2" placeholder="Search invitation..." onChange={this.handleQueryChange.bind(this)} />
        </div>
        <header className="jumbotron">
        <table className="table table-striped">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">UserName</th>
              <th scope="col">Email</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
          {this.filterInvitation().map((invitation, i) => (
            <tr key={i}>
              <th scope="row"> {invitation.id} </th>
              <td> {invitation.sender.username} </td>
              <td> {invitation.sender.email} </td>
              <td> 
                {
                  invitation.status == 0
                  ?
                  <div>
                    <button type="button" onClick={this.handleAcceptClick.bind(this, invitation.id)} className="btn btn-success">Accepte</button>
                    <button type="button" onClick={this.handleRefuseClick.bind(this, invitation.id)} className="btn btn-danger">Refuser</button>
                  </div>
                  :
                  invitation.status == 1
                  ?
                  "accepted"
                  :
                  "refused"
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