import 'package:flutter/material.dart';
import 'package:front/models/core/User.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/providers/OrganizationDialogProvider.dart';
import 'package:front/views/homePage/dialog/CreateOrganizationDialog.dart';
import 'package:provider/provider.dart';

Widget listOrganizationWidget({
  required OrganizationDialogProvider organizationDialogProvider,
  required BuildContext context,
}) {
  return Stack(
    children: [
      Selector<AuthenticationProvider, User>(
          selector: (context, provider) => provider.user,
          builder: (context, data, child) {
            return Column(
              children: <Widget>[
                    Padding(
                      padding:
                          const EdgeInsets.only(top: 10, left: 10, bottom: 10),
                      child: Text(
                        "My Organizations : ",
                        style: TextStyle(fontSize: 20),
                      ),
                    ),
                  ] +
                  data.listOrganization.map<Widget>((organization) {
                    return Card(
                      elevation: 3,
                      child: ListTile(
                        title: Text(organization.name),
                        onTap: () {
                          organizationDialogProvider.currentOrganization =
                              organization;
                        },
                      ),
                    );
                  }).toList(),
            );
          }),
      Positioned(
        bottom: 20,
        left: 10,
        child: Container(
          alignment: Alignment.center,
          padding: EdgeInsets.only(bottom: 30, top: 10),
          color: Colors.white,
          child: ElevatedButton(
            onPressed: () {
              CreateOrganizationDialog(context);
            },
            child: Container(
              padding: EdgeInsets.all(5),
              child: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Icon(
                    Icons.add,
                  ),
                  Text("New Organization"),
                ],
              ),
            ),
          ),
        ),
      ),
    ],
  );
}
