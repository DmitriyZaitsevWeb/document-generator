import axios from 'axios';

class Repository {

    static get(id, includes = []) {
        return new Promise((resolve, reject) => {
            return axios.get(this.fullUrl(id, includes))
                .then(({ data }) => resolve(data.data))
                .catch(({ data }) => reject(data.error))
                ;
        })
    }

    static getByGuid(guid) {
        return new Promise((resolve, reject) => {
            return axios.get(`${this.url}${guid}`)
                .then(({ data }) => resolve(data.data))
                .catch(({ data }) => reject(data.error))
                ;
        })
    }

    static create(data) {
        return new Promise((resolve, reject) => {
            return axios.post(this.url, data)
                .then(({ data }) => resolve(data.data))
                .catch(({ data }) => reject(data.error))
                ;
        })
    }

    static update(id, data) {
        return new Promise((resolve, reject) => {
            return axios.put(this.url + id, data)
                .then(({ data }) => resolve(data.data))
                .catch(({ data }) => reject(data.error))
                ;
        })
    }

    static delete(id) {

    }

    static all() {
        return new Promise((resolve, reject) => {
            return axios.get(this.url)
                .then(({ data }) => resolve(data.data))
                .catch(({ data }) => reject(data.error))
                ;
        })
    }

    static allPaginated(page) {
        return new Promise((resolve, reject) => {
            return axios.get(`${this.url}` + (page ? `/?page=${page}` : ``))
                .then(({ data }) => resolve(data))
                .catch(({ data }) => reject(data.error))
                ;
        })
    }

    static fullUrl(id, includes) {
        var url = this.url + id
        if (includes.length > 0) {
            url += '?include=' + includes.join(',')
        }
        return url
    }
}

Repository.url = '';

export default Repository