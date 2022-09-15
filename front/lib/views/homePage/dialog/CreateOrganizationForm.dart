import 'package:flutter/material.dart';
import 'package:front/models/helper/OrganizationHelper.dart';
import 'package:front/providers/OrganizationProvider.dart';
import 'package:provider/provider.dart';

Widget CreateOrganizationForm(BuildContext context) {
  OrganizationHelper organizationHelper = OrganizationHelper(context: context);
  OrganizationProvider organizationProvider =
      Provider.of<OrganizationProvider>(context, listen: false);
  GlobalKey<FormState> organizationKey = GlobalKey<FormState>();
  return Form(
    key: organizationKey,
    child: Container(
      width: 600,
      margin: EdgeInsets.fromLTRB(20.0, 30.0, 20.0, 10.0),
      child: Column(mainAxisSize: MainAxisSize.min, children: [
        Text(" Name for your new organization"),
        SizedBox(height: 10),
        TextFormField(
          maxLength: 25,
          decoration: const InputDecoration(
              labelText: 'Nom de l\'organization',
              counterText: "",
              hintText: 'Entrer du texte',
              border: OutlineInputBorder()),
          validator: (value) {
            if (value == null) {
              return 'Veuillez saisir un texte';
            }
            organizationProvider.name = value;
            return null;
          },
        ),
        SizedBox(height: 10),
        ElevatedButton(
          onPressed: () {
            if (organizationKey.currentState!.validate()) {
              organizationHelper.createOrganization(
                  name: organizationProvider.name);
            }
            Navigator.of(context).pop();
          },
          child: Text("Add Organization"),
        ),
        SizedBox(height: 10),
      ]),
    ),
  );
}
