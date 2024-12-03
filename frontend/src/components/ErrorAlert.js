import { useEffect } from "react";
import { Alert } from "react-native";

const ErrorAlert = ({ errors }) => {

    useEffect(() => {
        if (errors) {
            const messages = Object.keys(errors).map((key) => errors[key]).join('\n');
            Alert.alert('Ops...', messages.trim());
        }

    }, [errors]);

    return null;
}

export default ErrorAlert;