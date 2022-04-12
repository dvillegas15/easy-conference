import { mapGetters, mapMutations } from "vuex";

import {
    SET_CURRENT_CLIENT,
    SET_CURRENT_GUARD,
    SET_CURRENT_UNIT,
    SET_CURRENT_VENUE,
    SET_CURRENT_USER,
    SET_CURRENT_RESOURCE,
    SET_PERSON_ROLES,
    SET_CURRENT_PERSON,
} from "@store/_mutations";

export default {
    data: () => ({
        breakpoint: "",
    }),
    computed: {
        ...mapGetters([
            "capabilities",
            "currentUser",
            "currentClient",
            "currentGuard",
            "currentPerson",
            "currentProfile",
            "currentResource",
            "currentUnit",
            "currentVenue",
            "currentWorkplace",
            "inProgress",
            "isAuthenticated",
            "personRoles",
            "systemParams",
        ]),
        isMobileBrowser() {
            return navigator.userAgentData.mobile;
        },
        isSafariBrowser() {
            return (
                navigator.userAgent.indexOf("Safari") > -1 &&
                navigator.userAgent.indexOf("Chrome") === -1
            );
        },
    },
    methods: {
        ...mapMutations({
            setCurrentClient: SET_CURRENT_CLIENT,
            setCurrentGuard: SET_CURRENT_GUARD,
            setCurrentUnit: SET_CURRENT_UNIT,
            setCurrentVenue: SET_CURRENT_VENUE,
            setCurrentUser: SET_CURRENT_USER,
            setCurrentResource: SET_CURRENT_RESOURCE,
            setCurrentPerson: SET_CURRENT_PERSON,
        }),
        openModal(modalId) {
            if (!modalId) return;
            this.$bvModal.show(modalId);
        },
        closeModal(modalId) {
            if (!modalId) return;
            this.$nextTick(() => this.$bvModal.hide(modalId));
        },
        userCan(capability) {
            if (!this.currentUser || !this.currentProfile) return false;
            if (["super-admin"].includes(this.currentProfile.slug)) return true;
            return this.capabilities.includes(capability);
        },
        getSystemParam(paramKey, instead = null) {
            const param = this.systemParams.find(
                (item) => item.key === paramKey.toUpperCase()
            );
            return param ? param.value : instead;
        },
        fileToBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = (error) => reject(error);
            });
        },
        async confirm(title) {
            return await this.$bvModal.msgBoxConfirm(title, {
                title: "Por favor confirme",
                size: "sm",
                buttonSize: "sm",
                okVariant: "danger",
                okTitle: "Confirmar",
                cancelTitle: "Cancelar",
                footerClass: "p-2",
                hideHeaderClose: false,
                centered: true,
            });
        },
    },
    mounted() {
        this.breakpoint = getBreakpoint();
        window.addEventListener(
            "resize",
            () => (this.breakpoint = getBreakpoint()),
            false
        );
    },
};

export const cookies = {
    methods: {
        setCookie(cname, cvalue, exdays = 365) {
            var d = new Date();
            d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        },
        getCookie(cname) {
            var name = cname + "=";
            var decodedCookie = decodeURIComponent(document.cookie);
            var ca = decodedCookie.split(";");
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        },
    },
};
