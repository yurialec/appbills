import { Button, StyleSheet, Text, View, Image, TextInput, TouchableOpacity, Alert } from 'react-native';
import { useNavigation } from '@react-navigation/native';
import { useState } from 'react';
import api from '../../config/api';
import * as yup from 'yup';
import { ContainerLogin } from '../../styles/custom';

export default function Login() {

    const navigation = useNavigation();

    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');

    const loginSubmit = async () => {

        try {
            await validationSchema.validate({ email, password }, { abortEarly: false });

            await api.post('/login', {
                email, password
            }).then(function (response) {
                Alert.alert('Sucesso', response.data.user.email);
            }).catch(function (error) {
                if (error.response) {
                    Alert.alert("Ops", error.response.data.message)
                } else {
                    Alert.alert("Ops", "Tente novamente mais tarde.")
                }
            });
        } catch (error) {
            Alert.alert('Ops', error.errors[0])
        }

    }

    const validationSchema = yup.object().shape({
        email: yup.string('Necessário preencher o e-mail')
            .required('Necessário preencher o e-mail'),
        password: yup.string('Necessário preencher a senha')
            .required('Necessário preencher a senha')
    });

    return (
        <ContainerLogin>

            <View style={styles.logo}>
                <Image style={styles.img} source={require('../../../assets/logo.png')} />
            </View>

            <TextInput
                style={styles.inputForm}
                placeholder='Usuário'
                autoCorrect={false}
                keyboardType='email-address'
                autoCapitalize='none'
                value={email}
                onChangeText={text => setEmail(text)}
            />

            <TextInput
                style={styles.inputForm}
                placeholder='Senha'
                autoCorrect={false}
                secureTextEntry={true}
                value={password}
                onChangeText={text => setPassword(text)}
            />

            <TouchableOpacity style={styles.btnSubmitForm} onPress={loginSubmit}>
                <Text style={styles.txtSubmit}>Acessar</Text>
            </TouchableOpacity>

            <Text style={styles.linkLogin} onPress={() => navigation.navigate('NewUser')}>Cadastrar</Text>

            <Text style={styles.linkLogin} onPress={() => navigation.navigate('RecoverPassword')}>Recuperar Senha</Text>
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