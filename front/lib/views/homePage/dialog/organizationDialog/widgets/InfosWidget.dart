import 'package:flutter/material.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/models/core/UserOrg.dart';
import 'package:front/models/helper/OrganizationHelper.dart';
import 'package:front/providers/OrganizationDialogProvider.dart';
import 'package:front/views/homePage/dialog/JoinOrganizationDialog.dart';
import 'package:provider/provider.dart';

Widget infosWidget({
  required Organization organization,
  required BuildContext context,
}) {
  OrganizationHelper organizationHelper = OrganizationHelper(context: context);
  return Column(
    children: [
      Container(
        padding: EdgeInsets.all(15),
        child: Row(
          children: [
            Text(
              "Owner : ${organization.owner.firstname} ${organization.owner.lastName}",
            ),
            Spacer(),
            Padding(
              padding: const EdgeInsets.all(8.0),
              child: ElevatedButton(
                style: ButtonStyle(
                  backgroundColor:
                      MaterialStateProperty.all<Color>(Colors.white),
                ),
                onPressed: () async {
                  organizationHelper.deleteOrganization(
                      idOrganization: organization.id);
                },
                child: Row(
                  children: [
                    Text(
                      "Delete Organization",
                      style: TextStyle(color: Colors.red),
                    ),
                    Icon(
                      Icons.delete,
                      color: Colors.red,
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
      Container(
        height: 50,
        margin: EdgeInsets.only(right: 8),
        padding: EdgeInsets.symmetric(horizontal: 10),
        alignment: Alignment.center,
        color: Colors.grey.shade200,
        child: Row(
          children: [
            Text("User list"),
            Spacer(),
            IconButton(
              onPressed: () {
                JoinOrganizationDialog(
                    context: context, idOrganization: organization.id);
              },
              icon: Icon(
                Icons.add,
              ),
            ),
          ],
        ),
      ),
      FutureBuilder<void>(
          future: organizationHelper.getOrganizationUsers(
              idOrganization: organization.id),
          builder: ((context, snapshot) {
            if (snapshot.connectionState != ConnectionState.done) {
              return Container();
            }
            return Selector<OrganizationDialogProvider, List<UserOrg>>(
                selector: (context, provider) => provider.listUserOrg,
                builder: (context, data, child) {
                  return SingleChildScrollView(
                    child: Column(
                      children: data.map<Widget>((userOrg) {
                        return Container(
                          padding: EdgeInsets.symmetric(
                              vertical: 10, horizontal: 10),
                          child: Row(
                            children: [
                              Text(
                                "${userOrg.firstname} ${userOrg.lastName} ${userOrg.email}",
                              ),
                              Spacer(),
                              userOrg.id == organization.owner.id
                                  ? Container()
                                  : IconButton(
                                      onPressed: () {
                                        organizationHelper.leaveOrganization(
                                            idUser: userOrg.id,
                                            idOrganization: organization.id);
                                      },
                                      icon: Icon(
                                        Icons.remove,
                                        color: Colors.red,
                                      ),
                                    ),
                            ],
                          ),
                        );
                      }).toList(),
                    ),
                  );
                });
          })),
    ],
  );
}
