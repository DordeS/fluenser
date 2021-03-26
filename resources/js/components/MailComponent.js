import React, { Component } from 'react';
import { Route, Link } from 'react-router-dom';
import { InboxComponent } from './mailcomponents/InboxComponent';
import { RequestComponent } from './mailcomponents/RequestComponent';
import { RequestDetailComponent } from './mailcomponents/RequestDetailComponent';
import { ChatComponent } from './mailcomponents/ChatComponent';
import API from './api';
import $ from 'jquery';

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

  selectTab(tabName) {
    // console.log(tabName);
    $("#messageTab a.active").removeClass('active');
    $("#messageTab #"+ tabName).addClass('active');
  }

  render() {
    if (this.state.showItem == 'mail') {
      return (
        <div className="w-full md:max-w-7xl mx-auto px-2 h-8" style={{borderBottom: '1px solid lightgray'}} id="messageTab">
          <Link to="/inbox" className="px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 mx-4 active" id="inbox">INBOX</Link>
          <Link to="/request" className="px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 mx-4" id="requests">REQUESTS</Link>
  
          <Route path="/inbox">
            <InboxComponent 
              inboxClickEvent = {(inboxID) => this.handleInboxClick(inboxID)}
              selectTab = {(tabName) => this.selectTab(tabName)}
            />
          </Route>
          <Route path="/request">
            <RequestComponent
              requestClickEvent = {(requestID) => this.handleRequestClick(requestID)}
              selectTab = {(tabName) => this.selectTab(tabName)}
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