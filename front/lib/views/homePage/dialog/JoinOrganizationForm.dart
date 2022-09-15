import 'package:flutter/material.dart';
import 'package:front/models/helper/OrganizationHelper.dart';
import 'package:front/providers/OrganizationProvider.dart';
import 'package:provider/provider.dart';

Widget JoinOrganizationForm(
    {required BuildContext context, required int idOrganization}) {
  OrganizationHelper organizationHelper = OrganizationHelper(context: context);
  OrganizationProvider organizationProvider =
      Provider.of<OrganizationProvider>(context, listen: false);
  GlobalKey<FormState> joinOrganizationKey = GlobalKey<FormState>();
  return Form(
    key: joinOrganizationKey,
    child: Container(
      width: 600,
      margin: EdgeInsets.fromLTRB(20.0, 30.0, 20.0, 10.0),
      child: Column(mainAxisSize: MainAxisSize.min, children: [
        Text('Enter an email, or multiple separated with ","'),
        SizedBox(height: 10),
        TextFormField(
          maxLength: 25,
          decoration: const InputDecoration(
              labelText: 'Email',
              counterText: "",
              border: OutlineInputBorder()),
          validator: (value) {
            if (value == null) {
              return 'Veuillez saisir un texte';
            }
            organizationProvider.joinEmail = value;
            return null;
          },
        ),
        SizedBox(height: 10),
        ElevatedButton(
          onPressed: () {
            if (joinOrganizationKey.currentState!.validate()) {
              List<String> listEmail =
                  organizationProvider.joinEmail.split(',');
              for (String email in listEmail) {
                organizationHelper.joinOrganization(
                    email: email, idOrganization: idOrganization);
              }
            }
            Navigator.of(context).pop();
          },
          child: Text("Add email"),
        ),
        SizedBox(height: 10),
      ]),
    ),
  );
}
