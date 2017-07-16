import shop from '../../api/shop'
import * as types from '../mutation-types'

// initial state
const state = {
    _developer: [],
}

// getters
const getters = {
    developer: state => state._developer,
}

// actions
const actions = {
    getDeveloper({ commit }, developerId) {
        shop.getDeveloper(developer => {
            commit(types.RECEIVE_DEVELOPER, { developer });
        }, developerId)
    }
}

// mutations
const mutations = {
    [types.RECEIVE_DEVELOPER] (state, { developer }) {
        state._developer = developer
    },
}

export default {
    state,
    getters,
    actions,
    mutations
}
