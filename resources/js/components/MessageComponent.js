import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Message extends Component {
  constructor() {
    super();
    this.state = {
      message:[]
    };
  }

  componentDidMount()
  {
    console.log("mounted component message");
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('da7cd3b12e18c9e2e461', {
      cluster: 'eu',
    });
    const this2 = this
    var channel = pusher.subscribe('fluenser-channel');
    channel.bind('fluenser-event', function(data) { 
      const message = this2.state.message;
      message.push(data.data);
      console.log(message);
      this2.setState({message:message});
    }); 
  }

  render() {
      return (
        <div className="container">
          <p>I'm an component message 2!</p>
          {
            this.state.message.map((msg)=>{
              return(
                <p> <b>{msg.user} </b> = {msg.message}</p>                  
              )
            })
          }          
        </div>
      );
  }
}

if (document.getElementById('message-component')) {
    ReactDOM.render(<Message />, document.getElementById('message-component'));
}