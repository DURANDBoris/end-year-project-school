import 'package:flutter/material.dart';
import 'package:front/providers/AccountProvider.dart';

Widget identityAccountForm(AccountProvider accountProvider) {
  return Row(
    children: [
      SizedBox(
        width: 210,
        child: TextFormField(
          validator: (value) {
            if (value == null || value.isEmpty) return "Enter lastName";
            accountProvider.lastName = value;
            return null;
          },
          decoration: const InputDecoration(
            labelText: 'LastName',
          ),
        ),
      ),
      SizedBox(width: 10),
      SizedBox(
        width: 180,
        child: TextFormField(
          validator: (value) {
            if (value == null || value.isEmpty) return "Enter firstName";
            accountProvider.firstName = value;
            return null;
          },
          decoration: const InputDecoration(
            labelText: 'FirstName',
          ),
        ),
      ),
    ],
  );
}
