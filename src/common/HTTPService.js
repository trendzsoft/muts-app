import axios from 'axios';

export default class HTTPService {

    SetCUId = (id) => {
        this.cuid = id;
    }

    loadapi = (api, data = {}, spin = true) => {
        return new Promise((resolve) => {
            if (spin) {
                window.application.spinOn();
            }
            if (this.cuid) {
                data.cuid = this.cuid;
            }
           // this.interval = setInterval(() => {this.serve(api, data, spin, resolve)}, 10000);
        });
    }
    serve = (api, data = {}, spin, resolve) => {
        return axios.get(`http://harisautomation.com/global/api/${api}`,
            data
        ).then(response => {
            clearInterval(this.interval);
            resolve(response);
            return response;
        }).catch(e => {
            return false;
            //  return setTimeout(() =>{ this.loadapi(api, data)}, 990000);
        }).then(response => {
            window.application.spinOff();
            return response;
        });
    }
    HTTPserve = (api, data = {}, result) => {
        return axios.get(`http://harisautomation.com/global/api/${api}`,
            data
        ).then(response => {
            result = response.data;
            return response;
        }).catch(e => {
            return false;
            //  return setTimeout(() =>{ this.loadapi(api, data)}, 990000);
        }).then(response => {
            window.application.spinOff();
            return response;
        });
    }
}