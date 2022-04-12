import * as actions from '@store/_actions';
import * as mutations from '@store/_mutations';
export default {
    async [actions.SET_CURRENT_PROFILE]({
        commit
    }, profile) {
        commit(mutations.SET_CURRENT_PROFILE, profile);
        let menu = [];
        profile.menu_items.map(menuItem => {
            menu.findIndex(item => item.id === menuItem.menu_id) === -1 && (menu.push({
                ...menuItem.menu,
                menuItems: profile.menu_items.filter(x => x.menu_id === menuItem.menu_id)
            }))
        });
        commit(mutations.SET_MENU, menu);
    }
}
