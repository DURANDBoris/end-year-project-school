class UserOrg {
  int id;
  String firstname;
  String lastName;
  String email;

  UserOrg({
    required this.id,
    required this.firstname,
    required this.lastName,
    required this.email,
  });

  factory UserOrg.fromJson(Map<String, dynamic> json) {
    try {
      return UserOrg(
        id: json["id"],
        firstname: json["firstname"].toString(),
        lastName: json["lastname"].toString(),
        email: json["email"].toString(),
      );
    } catch (e) {
      print("catch owner");
      return UserOrg(
        id: 0,
        firstname: "",
        lastName: "",
        email: "",
      );
    }
  }
}
