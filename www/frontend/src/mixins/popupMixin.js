import {ElMessageBox} from "element-plus";

export default {
    methods: {
        show(title, message) {
            ElMessageBox.alert(message, title, {
                confirmButtonText: 'OK'
            });
        }
    }
}