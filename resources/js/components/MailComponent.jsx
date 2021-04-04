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

  const handleChatClick = (user1_id, user2_id) => {
    console.log(user1_id, user2_id);
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('checkInbox/' + user1_id + '/' + user2_id + '?api_token=' + api_token, {headers:headers}).then((res) => {
      if(res.status == 200) {
        console.log(res.data.inbox_id);
        setShowItem('chat');
        setInboxID(res.data.inbox_id);
      }
    }).catch(err => {console.log(err)});
  }

  const selectTab = (tabName) => {
    $("#messageTab a.active").removeClass('active');
    $("#messageTab #"+ tabName).addClass('active');
  }

  const afterDeposit = () => {
    history.push('/collaborations');
    window.location.reload();
  }

  const handleReview = (request_id) => {
    console.log('review');
    history.push('/leaveReview/' + request_id);
    window.location.reload();
  }

  const handleRequestClick = (requestID) => {
    const headers ={
      'Accept': 'application/json'
    };
    var api_token = $("meta[name=api-token]").attr('content');
    API.get('read/request/' + requestID + '?api_token=' + api_token, {headers:headers}).then((res) => {
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

  if (showItem == 'mail') {
    return (
      <div 
        className="w-full md:max-w-7xl mx-auto px-2 h-8"
        style={{borderBottom: '1px solid lightgray'}} 
        id="messageTab"
      >
        <Link
          to="/inbox"
          className="px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 mx-4 active" 
          id="inbox"
        >
          INBOX
        </Link>

        <Link
          to="/request"
          className="px-1 pt-2 pb-1 font-bold text-sm md:text-md leading-8 mx-4" 
          id="requests"
        >
          REQUESTS
        </Link>

        <Route path="/inbox" exact>
          <InboxComponent 
            inboxClickEvent = {(inboxID) => {
              setInboxID(inboxID);
              setShowItem('chat');
            }}
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
          leaveReview = {(request_id) => handleReview(request_id)}
        />
      )
    } else {
      console.log(showItem);
      return (
        <RequestDetailComponent 
          requestID = {requestID}
          back = {() => setShowItem('mail')}
          onChatClick = {(user1_id, user2_id) => handleChatClick(user1_id, user2_id)}
          afterDeposit = {() => afterDeposit()}
        />
      )
    }
  }
}

export default Mail;