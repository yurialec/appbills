import { Image, StyleSheet, Text, View, TouchableOpacity, TextInput, Alert } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useState } from 'react';
import * as yup from 'yup';
import api from '../../config/api';
import ErrorAlert from '../../components/ErrorAlert';
import { ContainerLogin } from '../../styles/custom';

export default function NewUser() {

    const navigation = useNavigation();

    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [errors, setErrors] = useState(null);

    const addUser = async () => {

        try {

            await validationSchema.validate({ name, email, password }, { abortEarly: false });

            await api.post('/register', { name, email, password })
                .then((response) => {

                    navigation.navigate('Login');
                }).catch((err) => {

                    if (err) {
                        const errors = err.response?.data?.errors;
                        setErrors(errors);
                    } else {
                        Alert.alert('Ops', 'Tente novamente.');
                    }
                });

        } catch (error) {
            if (error.errors) {
                Alert.alert('Ops', error.errors[0]);
            } else {
                Alert.alert('Ops', 'Tente novamente mais tarde');
            }
        }
    }

    const validationSchema = yup.object().shape({
        name: yup.string('Necessário preencher o nome')
            .required('Necessário preencher o nome'),
        email: yup.string()
            .email("Por favor, insira um email válido")
            .matches(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                "Formato de email inválido")
            .required("O campo de email é obrigatório"),
        password: yup.string('Necessário preencher a senha')
            .required('Necessário preencher a senha')
            .min(8, 'A senha deve ter pelo menos 8 caracteres.')
            .matches(/[a-zA-Z]/, 'A senha deve conter letras.')
            .matches(/[0-9]/, 'A senha deve conter números.')
            .matches(/[@$!%*?&]/, 'A senha deve conter símbolos.')
    });

    return (
        <ContainerLogin>

            <ErrorAlert errors={errors} />

            <View style={styles.logo}>
                <Image style={styles.img} source={require('../../../assets/logo.png')} />
            </View>

            <TextInput
                style={styles.inputForm}
                placeholder='Nome Completo'
                value={name}
                onChangeText={text => setName(text)}
            />

            <TextInput
                style={styles.inputForm}
                placeholder='E-mail'
                autoCorrect={false}
                keyboardType='email-address'
                autoCapitalize='none'
                value={email}
                onChangeText={text => setEmail(text)}
            />

            <TextInput
                style={styles.inputForm}
                placeholder='Mínimo 6 caracteres'
                autoCorrect={false}
                secureTextEntry={true}
                value={password}
                onChangeText={text => setPassword(text)}
            />

            <TouchableOpacity style={styles.btnSubmitForm} onPress={addUser}>
                <Text style={styles.txtSubmit}>Cadastrar</Text>
            </TouchableOpacity>

            <Text style={styles.linkLogin} onPress={() => Navigation.navigate('Login')}>Login</Text>
        </ContainerLogin>
    );
}

const styles = StyleSheet.create({
    logo: {
        paddingBottom: 20,
    },
    img: {
        width: 150,
        height: 150,
        borderRadius: 30,
        borderWidth: 2,
        borderColor: 'red',
    },
    inputForm: {
        backgroundColor: '#f5f5f5',
        width: '90%',
        marginBottom: 15,
        color: '#10101c',
        fontSize: 18,
        borderRadius: 6,
        padding: 10,
    },
    btnSubmitForm: {
        backgroundColor: '#1f51fe',
        width: '90%',
        height: 45,
        alignItems: 'center',
        justifyContent: 'center',
        borderRadius: 6,
    },
    txtSubmit: {
        color: '#f5f5f5',
        fontSize: 18,
    },
    linkLogin: {
        color: '#1f51fe',
        marginTop: 10,
        fontSize: 18,
    }
});