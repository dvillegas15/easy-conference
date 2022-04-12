import Vue from 'vue'
import _state from './state'
import * as mutations from '@store/_mutations'
import ls from '../utils/localStorage'
export default {
    [mutations.SET_CAPABILITIES](state, capabilities) {
        state.capabilities = capabilities
        ls.save(state)
    },
    [mutations.SET_COUNCIL](state, council) {
        state.council = council
        ls.save(state)
    },
    [mutations.SET_CURRENT_MEETING](state, meeting) {
        state.currentMeeting = meeting
        ls.save(state)
    },
    [mutations.SET_STATE](state, data) {
        Object.keys(_state).forEach(
            key => data.hasOwnProperty(key) && Vue.set(state, key, data[key])
        )
    },
    [mutations.SET_CURRENT_USER](state, user) {
        state.currentUser = user
        ls.save(state)
    },
    [mutations.SET_CURRENT_CLIENT](state, client) {
        state.currentClient = client
        ls.save(state)
    },
    [mutations.SET_CURRENT_GUARD](state, guard) {
        state.currentGuard = guard
        ls.save(state)
    },
    [mutations.SET_CURRENT_RESOURCE](state, resource) {
        state.currentResource = resource
        ls.save(state)
    },
    [mutations.SET_CURRENT_PERSON](state, person) {
        state.currentPerson = person
        ls.save(state)
    },
    [mutations.SET_CURRENT_PROFILE](state, profile) {
        state.currentProfile = profile
        ls.save(state)
    },
    [mutations.SET_CURRENT_SHIFT](state, shift) {
        state.currentShift = shift
        ls.save(state)
    },
    [mutations.SET_CURRENT_UNIT](state, unit) {
        const configurations = {}
        if (!!unit.configurations) {
            unit.configurations.map(
                item => (configurations[item.key] = item.value)
            )
        }
        state.currentUnit = {
            ...unit,
            configurations,
        }
        ls.save(state)
    },
    [mutations.SET_CURRENT_VENUE](state, venue) {
        state.currentVenue = venue
        ls.save(state)
    },
    [mutations.SET_CURRENT_WORKPLACE](state, workplace) {
        state.currentWorkplace = workplace
        ls.save(state)
    },
    [mutations.SET_MENU](state, menu) {
        state.menu = menu
        ls.save(state)
    },
    [mutations.SET_CURRENT_PROFILE](state, profile) {
        state.currentProfile = profile
        ls.save(state)
    },
    [mutations.ADD_PENDING_REQUESTS](state) {
        state.pendingRequests = state.pendingRequests + 1
    },
    [mutations.SUB_PENDING_REQUESTS](state) {
        state.pendingRequests = Math.max(state.pendingRequests - 1, 0)
    },
    [mutations.SET_PERSON_ROLES](state, roles) {
        state.personRoles = roles
        ls.save(state)
    },
    [mutations.SET_POLLS](state, polls) {
        state.polls = polls
    },
    [mutations.SET_SYSTEM_PARAMS](state, params) {
        state.systemParams = params
    },
    [mutations.SET_USER_POLLS_READY](state, pollIds) {
        state.userPollsReady = pollIds
        ls.save(state)
    },
    [mutations.SET_CURRENT_SURVEY](state, survey) {
        state.currentSurvey = survey
        ls.save(state)
    },
    [mutations.SET_CURRENT_EVENT](state, event) {
        state.currentEvent = event
        ls.save(state)
    }
}
