import 'package:flutter/material.dart';
import 'package:front/providers/SignInProvider.dart';
import 'package:provider/provider.dart';

Widget signInForm(
    SignInProvider signInProvider, GlobalKey<FormState> signInKey) {
  return Form(
    key: signInKey,
    child: Column(
      children: [
        TextFormField(
          validator: ((value) {
            if (value == null || value.isEmpty) return "Login required";
            signInProvider.username = value;
            return null;
          }),
          decoration: const InputDecoration(
            icon: Icon(Icons.person),
            labelText: 'Login',
          ),
        ),
        const SizedBox(height: 10),
        Selector<SignInProvider, bool>(
            selector: (_, provider) => provider.isObscure,
            shouldRebuild: (previous, next) => true,
            builder: (_, isObscure, __) {
              return TextFormField(
                validator: ((value) {
                  if (value == null || value.isEmpty) {
                    return "Password required";
                  }
                  signInProvider.password = value;
                  return null;
                }),
                obscureText: isObscure,
                decoration: InputDecoration(
                  icon: const Icon(Icons.key),
                  labelText: 'Password',
                  suffixIcon: IconButton(
                    highlightColor: Colors.transparent,
                    hoverColor: Colors.transparent,
                    splashColor: Colors.transparent,
                    onPressed: () {
                      signInProvider.isObscure = !isObscure;
                    },
                    icon: Icon(
                      isObscure ? Icons.visibility : Icons.visibility_off,
                    ),
                  ),
                ),
              );
            }),
      ],
    ),
  );
}
