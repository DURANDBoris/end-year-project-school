import 'package:flutter/material.dart';
import 'package:front/providers/AccountProvider.dart';
import 'package:provider/provider.dart';
import 'package:tuple/tuple.dart';

Widget verifyContentPassword() {
  return Selector<AccountProvider, Tuple5<bool, bool, bool, bool, bool>>(
      selector: (_, provider) => Tuple5(provider.isCap, provider.isMin,
          provider.isNum, provider.isSpecial, provider.isLong),
      builder: (_, data, __) {
        return Column(
          children: [
            RowIs("Contient une majuscule", data.item1),
            RowIs("Contient une minuscule", data.item2),
            RowIs("Contient un chiffre", data.item3),
            RowIs("Contient un caractère spécial", data.item4),
            RowIs("Contient au moins 8 caractères", data.item5),
          ],
        );
      });
}

Widget RowIs(String string, bool value) {
  return Row(
    children: [
      value
          ? Icon(
              Icons.check,
              color: Colors.green,
            )
          : Icon(
              Icons.close,
              color: Colors.red,
            ),
      Text(
        string,
        style: TextStyle(color: value ? Colors.green : Colors.red),
      )
    ],
  );
}
