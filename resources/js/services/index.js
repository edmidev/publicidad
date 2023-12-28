import axios from './axios'
import CONST from './consts'

const validatePhoneNumber = (event) => {
    if (event.keyCode == 43) {
        // do nothing
    } else if (event.keyCode >= 48 && event.keyCode <= 57){
        // do nothing
    }
    else {
        event.preventDefault()
    }
}

const validateNumber = (event) => {
    if (event.keyCode < 48 || event.keyCode > 57){
        event.preventDefault()
    }
}

export {
    axios,
    CONST,
    validatePhoneNumber,
    validateNumber
}
