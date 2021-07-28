import React, { Component } from "react";
import { Switch, Route, Link } from "react-router-dom";
import "bootstrap/dist/css/bootstrap.min.css";
import "./Home.css";

import AuthService from "./services/auth.service";

import Login from "./components/login.component";
import Register from "./components/register.component";
import Home from "./components/home.component";
import BoardUsers from "./components/board-users.component";
import InvitationSent from "./components/board-invitation-sent.component.js";
import InvitationsReceived from "./components/board-invitations-received.component";

class HomePage extends Component {
  constructor(props) {
    super(props);
    this.logOut = this.logOut.bind(this);

    this.state = {
      showModeratorBoard: false,
      showAdminBoard: false,
      currentUser: undefined,
    };
  }

  componentDidMount() {
    const user = AuthService.getCurrentUser();
    const username = AuthService.getCurrentUserName();

    if (user) {
      this.setState({
        currentUser: user,
        currentUserName: username,
      });
    }
  }

  logOut() {
    AuthService.logout();
  }

  render() {
    const { currentUser, showModeratorBoard, showAdminBoard } = this.state;

    return (
      <div>
        <nav className="navbar navbar-expand navbar-dark bg-dark">
          <Link to={"/"} className="navbar-brand">
             {this.state.currentUserName}
          </Link>
          <div className="navbar-nav mr-auto">
            {currentUser && (
              <li className="nav-item">
                <Link to={"/users"} className="nav-link">
                  Users
                </Link>
              </li>
            )}
             {currentUser && (
              <li className="nav-item">
                <Link to={"/invitation_sent"} className="nav-link">
                  Invitations sent
                </Link>
              </li>
            )}

            {currentUser && (
              <li className="nav-item">
                  <Link to={"/invitations_received"} className="nav-link">
                      Invitations received
                  </Link>
              </li>
            )}      
          </div>

          {currentUser ? (
            <div className="navbar-nav ml-auto">
              <li className="nav-item">
                <Link to={"/profile"} className="nav-link">
                  {currentUser.username}
                </Link>
              </li>
              <li className="nav-item">
                <a href="/login" className="nav-link" onClick={this.logOut}>
                  Logout
                </a>
              </li>
            </div>
          ) : (
            <div className="navbar-nav ml-auto">
              <li className="nav-item">
                <Link to={"/login"} className="nav-link">
                  Login
                </Link>
              </li>

              <li className="nav-item">
                <Link to={"/register"} className="nav-link">
                  Register
                </Link>
              </li>
            </div>
          )}
        </nav>

        <div className="container mt-3">
          <Switch>
            <Route exact path={["/", "/home"]} component={Home} />
            <Route exact path="/login" component={Login} />
            <Route exact path="/register" component={Register} />
            <Route path="/users" component={BoardUsers} />
            <Route path="/invitation_sent" component={InvitationSent} />
            <Route path="/invitations_received" component={InvitationsReceived} />
          </Switch>
        </div>
      </div>
    );
  }
}

export default HomePage;