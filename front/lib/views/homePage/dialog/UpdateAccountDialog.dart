import 'package:flutter/material.dart';
import 'package:front/views/formulates/UpdateAccountForm.dart';

void UpdateAccountDialog(BuildContext context) {
  showDialog(
      context: context,
      builder: (context) {
        return Dialog(
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(30.0)),
          child: UpdateAccountForm(context),
        );
      });
}
