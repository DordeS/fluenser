import React, { useState } from 'react';
import { Route, Link, Redirect, useHistory } from 'react-router-dom';
import InboxComponent from './mailcomponents/InboxComponent';
import RequestComponent from './mailcomponents/RequestComponent';
import RequestDetailComponent from './mailcomponents/RequestDetailComponent';
import ChatComponent from './mailcomponents/ChatComponent';
import API from './api';
import $ from 'jquery';

const Mail = (props) => {

  const [showItem, setShowItem] = useState('mail');
  const [inboxID, setInboxID] = useState(0);
  const [requestID, setrequestID] = useState(0);

  const history = useHistory();

  const selectTab = (tabName) => {
    $("#messageTab a.active").removeClass('active');
    $("#messageTab #"+ tabName).addClass('active');
  }

  const afterDeposit = () => {
    history.push('/collaborations');
    window.location.reload();
  }

  const handleRequestClick = (requestID) => {
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('read/request/' + requestID + '?api_token=' + api_token, {headers:headers})
      .then((res) => {
      if(res.status == 200) {
        console.log('ooooo');
        setrequestID(requestID);
        setShowItem('requestDetail');

        var count = $("#newInboxNotif").text();
        $("#newInboxNotif").text(parseInt(count) - 1);
        if(count == 1) {
          $("#newInboxNotif").hide();
        }
      }
    }).catch(err => {console.log(err)});
  }

  const handleInboxClick = (inboxID) => {
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('read/inbox/' + inboxID + '?api_token=' + api_token, {headers:headers})
      .then((res) => {
      if(res.status == 200) {
        console.log('ooooo');
        setInboxID(inboxID);
        setShowItem('chat');

        var count = $("#newInboxNotif").text();
        $("#newInboxNotif").text(parseInt(count) - 1);
        if(count == 1) {
          $("#newInboxNotif").hide();
        }
      }
    }).catch(err => {console.log(err)});
  }

  if (showItem == 'mail') {
    return (
      <div 
        className="w-full md:max-w-7xl mx-auto px-2 h-8"
        style={{borderBottom: '1px solid lightgray'}} 
        id="messageTab"
      >
        <Link
          to="/inbox"
          className="px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 mx-4 active relative" 
          id="inbox"
        >
          <div className="absolute w-2 h-2 rounded-full bg-red-500 top-1 -right-1 hidden" id="inboxNotif"></div>
          INBOX
        </Link>

        <Link
          to="/request"
          className="px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 mx-4 relative" 
          id="requests"
        >
          <div className="absolute w-2 h-2 rounded-full bg-red-500 top-1 -right-1 hidden" id="requestNotif"></div>
          REQUESTS
        </Link>

        <Route path="/inbox" exact>
          <InboxComponent 
            onInboxClick = {(inboxID) => handleInboxClick(inboxID)}
            selectTab = {(tabName) => selectTab(tabName)}
          />
        </Route>

        <Route path="/request" exact>
          <RequestComponent
            onRequestClick = {(requestID) => handleRequestClick(requestID)}
            selectTab = {(tabName) => selectTab(tabName)}
          />
        </Route>
      </div>
    );
  } else {
    if (showItem == 'chat') {
      return (
        <ChatComponent 
          inboxID = {inboxID}
          back = {() => setShowItem('mail')}
          inboxClickEvent = {(inboxID) => handleInboxClick(inboxID)}
          />
      )
    } else {
      console.log(showItem);
      return (
        <RequestDetailComponent 
          requestID = {requestID}
          back = {() => setShowItem('mail')}
          afterDeposit = {() => afterDeposit()}
          onRequestClick = {(requestID) => handleRequestClick(requestID)}
          />
      )
    }
  }
}

export default Mail;