import React, { Component } from 'react';
import { Route, Link } from 'react-router-dom';
import { InboxComponent } from './mailcomponents/InboxComponent';
import { RequestComponent } from './mailcomponents/RequestComponent';
import { RequestDetailComponent } from './mailcomponents/RequestDetailComponent';
import { ChatComponent } from './mailcomponents/ChatComponent';

export default class Mail extends Component {
  constructor() {
    super();
    this.state = {
      showItem: 'mail',
      inboxID: 0,
      requestID: 0,
    };
  }

  back() {
    this.setState({
      showItem: 'mail',
    });
  }

  handleInboxClick(inboxID) {
    this.setState({
      showItem: 'chat',
      inboxID: inboxID,
    });
  }

  handleRequestClick(requestID) {
    this.setState({
      showItem: "requestDetail",
      requestID: requestID,
    });
  }

  render() {
    if (this.state.showItem == 'mail') {
      return (
        <div className="w-full mx-auto">
          <div className="w-1/2 grid grid-cols-2 gap-y-1 mx-auto" id="tabMenu">
            <div className="col-span-1 text-center text-md md:text-lg" style={{color: 'rgb(92,180,184)'}}>
              <Link to="/inbox" className="active">Inbox</Link>
            </div>
            <div className="col-span-1 text-center text-md md:text-lg" style={{color: 'rgb(92,180,184)' }}>
              <Link to="/request">Requests</Link>
            </div>
          </div>
  
          <Route path="/inbox">
            <InboxComponent 
              inboxClickEvent = {(inboxID) => this.handleInboxClick(inboxID)}
            />
          </Route>
          <Route path="/request">
            <RequestComponent
              requestClickEvent = {(requestID) => this.handleRequestClick(requestID)}
            />
          </Route>
        </div>
      );
    } else {
      if (this.state.showItem == 'chat') {
        return (
          <ChatComponent 
            inboxID = {this.state.inboxID}
            back = {() => this.back()}
          />
        )
      } else {
        return (
          <RequestDetailComponent 
            requestID = {this.state.requestID}
          />
        )
      }
    }
  }
}