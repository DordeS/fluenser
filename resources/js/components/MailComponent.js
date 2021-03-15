import React, { Component } from 'react';
import { Route, Link } from 'react-router-dom';
import { InboxComponent } from './mailcomponents/InboxComponent';
import { RequestComponent } from './mailcomponents/RequestComponent';
import { RequestDetailComponent } from './mailcomponents/RequestDetailComponent';
import { ChatComponent } from './mailcomponents/ChatComponent';
import API from './api';

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

  handleChatClick(user1_id, user2_id) {
    console.log(user1_id, user2_id);
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('checkInbox/' + user1_id + '/' + user2_id + '?api_token=' + api_token, {headers:headers}).then((res) => {
      if(res.status == 200) {
        console.log(res.data.inbox_id);
        this.setState({
          showItem: 'chat',
          inboxID: res.data.inbox_id,
        })
      }
    }).catch(err => {console.log(err)});
  }

  render() {
    if (this.state.showItem == 'mail') {
      return (
        <div className="w-full mx-auto">
          <div className="w-1/2 grid grid-cols-2 gap-y-1 mx-auto" id="tabMenu" style={{marginTop:'10px'}}>
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
            back = {() => this.back()}
            onChatClick = {(user1_id, user2_id) => this.handleChatClick(user1_id, user2_id)}
          />
        )
      }
    }
  }
}