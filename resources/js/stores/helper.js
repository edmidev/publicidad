import { defineStore } from 'pinia'

const useHelperStore = defineStore('helper', {
    state: () => ({
        selectedItem:
            localStorage.getItem('selectedItem') === null
                ? null
                : JSON.parse(localStorage.getItem('selectedItem')),

        itemType:
            localStorage.getItem('itemType') === null
                ? null
                : localStorage.getItem('itemType'),
    }),
    actions: {
        setSelectedItem(selectedItem) {
            this.selectedItem = selectedItem
            localStorage.setItem('selectedItem', JSON.stringify(selectedItem))
        },
        setItemType(itemType) {
            this.itemType = itemType
            localStorage.setItem('itemType', itemType)
        },
        reset() {
            this.selectedItem = null
            this.itemType = null
            localStorage.removeItem('selectedItem')
            localStorage.removeItem('itemType')
        }
    },
})

export default useHelperStore
