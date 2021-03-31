import axios from 'axios';

export default axios.create({
  baseURL: 'http://exocoboten.nl/public/api/'
});